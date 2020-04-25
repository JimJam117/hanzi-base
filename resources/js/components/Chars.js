import React, {useEffect, useState, useRef, useCallback} from 'react'
import ReactDOM from 'react-dom';
import Select from 'react-select';

import { css } from "@emotion/core";
import ClipLoader from "react-spinners/ClipLoader";
import CharacterLink from './CharacterLink';


export default function Chars(props) {

    // abort controller
    var controller = new AbortController();
    var signal = controller.signal;


    const [sortBy, setSortBy] = useState('default');

    // filter states
    const [filterIsVisible, setFilterIsVisible] = useState(false)
    const [charsetFilter, setCharsetFilter] = useState('all')
    const [heisigFilter, setHeisigFilter] = useState('all')
    const [radicalFilter, setRadicalFilter] = useState(false)

    const [loading, setLoading] = useState(true);
    const [isFetching, setIsFetching] = useState(false);
    const [displayLoading, setDisplayLoading] = useState(false);
    
    const [originalResults, setOriginalResults] = useState([]); // this is used to keep the original order of the results after it has been sorted
    const [results, setResults] = useState([]);
    const [currentSearchHanzi, setCurrentSearchHanzi] = useState([]);

    // pagination state
    const [currentPage, setCurrentPage] = useState(1);
    const [lastPage, setLastPage] = useState();


    const observer = useRef()
    const lastCharacterRef = useCallback(
        (node) => {
            if(!loading && !(currentPage >= lastPage)) {
                if(observer.current) {observer.current.disconnect();}
                observer.current = new IntersectionObserver(entries => {
                    if(entries[0].isIntersecting) {
                        setDisplayLoading(true);
                        setTimeout(() => {
                            setCurrentPage(currentPage +1);
                            setDisplayLoading(false);
                            setLoading(true);
                        }, 500);
                        
                        
                        
                    }
                })
                if (node) {observer.current.observe(node)}
            }
        },
    )


    // pagination function
    const changePage = (pageToChangeTo) => {
        if(pageToChangeTo < 1 || pageToChangeTo > lastPage){
            console.log("Page to change to: " + pageToChangeTo + " is not within boundries");
        }
        else {
            setCurrentPage(pageToChangeTo);
            setLoading(true);
        }
    }

    // default all chars page
    let query = "/api/chars/index";
    // radical
    if (props.radical) {
        query = `/api/radical/search/${props.radical}`;
    } 
    else if (props.search) {
        query = `/api/search/${props.search}`;
    } 

    const fetchItems = async (sortUrl = "") =>  {
                setIsFetching(true);
                console.log(`${query}${sortUrl}?page=${currentPage}`);
                await fetch(`${query}${sortUrl}?page=${currentPage}`, {signal})
                    .then(async (response) => {
                        
                        //throw errors if issues
                        if (response.status === 500) {
                            console.log("500");
                        }
                        else if(response.status === 404) {
                            console.log("404");
                        }
                        else if(response.status === 419) {
                            console.log("419");
                        }
        
                        const data = await response.json();

                        // if there are already results
                        if(results.length > 0){
                            // the new results data
                            let newResults = data.chars.data;

                            // if the new results data has come back as an object instead of an array (happens sometimes)
                            // then convert it into an array before adding to the old results
                            if (typeof newResults === 'object' && newResults !== null) {
                                let newResultsArray = [];
                                Object.keys(newResults).map(function(key, index) {
                                    newResultsArray.push(newResults[key]);
                                });
                                newResults = newResultsArray;
                            }

                            setResults([...results, ...newResults]);
                        }
                        else{

                            let results = data.chars.data;

                            // if the results data has come back as an object instead of an array (happens sometimes)
                            // then convert it into an array
                            if (typeof results === 'object' && results !== null) {
                                let resultsArray = [];
                                Object.keys(results).map(function(key, index) {
                                    resultsArray.push(results[key]);
                                });
                                results = resultsArray;
                            }
                            
                            setResults(results);
                        }

                        //console.log(results);

                        if(data.hanzi) {
                            setCurrentSearchHanzi(data.hanzi);
                        }
                        setCurrentPage(data.chars.current_page);
                        setLastPage(data.chars.last_page);
                        setLoading(false);
                        setIsFetching(false);
                })
            }

    useEffect(() => {
        if (loading && !isFetching) {
            let sortUrl = `/sortBy/${sortBy}/${heisigFilter}/${charsetFilter}/${radicalFilter}`;
            fetchItems(sortUrl);
        }
        return () => {
            controller.abort();
        };
    }, [loading])

    // used for changing the filters or sorting
    const reset = () => {
        setCurrentPage(1);
        setResults([]);  
        setLoading(true);    
    }

    // filter / sorting functions
    const changeSortBy = (e) => {
        if(isFetching) {return}
        if(e.value == "heisig_number" && heisigFilter != "yes"){
            setHeisigFilter("yes");
        }
        reset();
        setSortBy(e.value);
    }


    const changeCharsetFilter = (e) => {
        if(isFetching) {return}
        reset();
        setCharsetFilter(e.value);
    }

    const changeHeisigFilter = (e) => {
        if(isFetching) {return}
        if(e.value == "no" && sortBy == "heisig_number"){
            setSortBy("default");
        }
        reset();
        setHeisigFilter(e.value); 
    }

    const radicalFilterChange = (e) => {
        if(isFetching) {return}
        reset();
        setRadicalFilter(e.target.checked);
    }

    let sortBySelectOptions = [
        {value: 'default', label: 'Default'},
        {value: 'updated_at', label: 'Recently Added'},
        {value: 'pinyin', label: 'Pinyin'},
        {value: 'freq', label: 'Frequency'},
        {value: 'stroke_count', label: 'Stroke Count'},
        {value: 'heisig_number', label: 'Heisig Number'},
    ];
    let charsetSelectOptions = [
        {value: 'all', label: 'Both'},
        {value: 'simp', label: 'Simplified'},
        {value: 'trad', label: 'Traditional'},

    ];
    let heisigSelectOptions = [
        {value: 'all', label: 'Include Heisig results'},
        {value: 'no', label: 'Exclude Heisig results'},
        {value: 'yes', label: 'Show only Heisig results'},
    ];


    return (
    
        <div>

            <div className="top-section">
            <button className="filters-button" value={filterIsVisible} onClick={() => setFilterIsVisible(!filterIsVisible)}>
                Filters {filterIsVisible ? <i className="fas fa-arrow-circle-up"></i> : <i className="fas fa-arrow-circle-down"></i>}
            </button>
          
            <div id="filters" className={"filter-section " + (!filterIsVisible && "filter-section-invisible")}>
            <label>Sorted By:
            <Select className="filter-select"
                value={sortBySelectOptions.find(o => o.value === sortBy)} 
                onChange={changeSortBy} 
                options={sortBySelectOptions} 

                theme={theme => ({
                    ...theme,
                    borderRadius: 0,
                    colors: {
                      ...theme.colors,
                      primary25: '#cd223d33',
                      primary50: '#cd223d7d',
                      primary75: '#cd223dc4',
                      primary: '#cd223d',
                }})}
            />
            </label>

            <label>Traditional/Simplified:
            <Select className="filter-select" 
                value={charsetSelectOptions.find(o => o.value === charsetFilter)} 
                onChange={changeCharsetFilter} 
                options={charsetSelectOptions} 

                theme={theme => ({
                    ...theme,
                    borderRadius: 0,
                    colors: {
                      ...theme.colors,
                      primary25: '#cd223d33',
                      primary50: '#cd223d7d',
                      primary75: '#cd223dc4',
                      primary: '#cd223d',
                }})}
            />
            </label>

            <label>Heisig:
            <Select className="filter-select" 
                value={heisigSelectOptions.find(o => o.value === heisigFilter)} 
                onChange={changeHeisigFilter} 
                options={heisigSelectOptions} 

                theme={theme => ({
                    ...theme,
                    borderRadius: 0,
                    colors: {
                      ...theme.colors,
                      primary25: '#cd223d33',
                      primary50: '#cd223d7d',
                      primary75: '#cd223dc4',
                      primary: '#cd223d',
                }})}
            />
            </label>

            


            {props.radical ? null :
                <label>
                    <input type="checkbox" checked={radicalFilter} onChange={(e) => radicalFilterChange(e)}/>
                    Show Only Radicals
                </label>
            }

            </div>

        </div>
            

            {results.length == 0 && !loading ? <div className="noResults">Sorry, no results found ;(</div> : null}

            <div className="characters_container">
                {
                    results.map((result, i) => {

                        return (<CharacterLink
                                        key={result.char.id}
                                        hasSimplified={result.hasSimplified}
                                        hasTraditional={result.hasTraditional}
                                        hanzi={result.char} 
                                        currentSearchHanzi={currentSearchHanzi}
                                        lastCharacterRef={lastCharacterRef}
                                        currentCharIndex={i} 
                                        resultsLength={results.length}/>
                                );
                    })
                }
            </div>
            
            {displayLoading || loading ? <div className="spinner"><ClipLoader /></div> : null}
            
          
        </div>
    )
}


if (document.getElementById('chars')) {
    const element = document.getElementById('chars');
    const props = Object.assign({}, element.dataset);
    ReactDOM.render(<Chars {...props}/>, element);
}