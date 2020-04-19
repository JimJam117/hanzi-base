import React, {useEffect, useState, useRef, useCallback} from 'react'
import ReactDOM from 'react-dom';

import { css } from "@emotion/core";
import ClipLoader from "react-spinners/ClipLoader";
import CharacterLink from './CharacterLink';


export default function Chars(props) {

    // abort controller
    var controller = new AbortController();
    var signal = controller.signal;


    const [sortBy, setSortBy] = useState('default');



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
            let sortUrl = "/sortBy/" + sortBy;
            fetchItems(sortUrl);
        }
        return () => {
            controller.abort();
        };
    }, [loading])

    

    const changeSortBy = (e) => {
        console.log(e.target.value);
        if(isFetching) {return} 
        setSortBy(e.target.value);
        setCurrentPage(1);
        setResults([]);
        setLoading(true);        
    }


    return (
    
        <div>
            <div style={{ color: 'red' }}>Sorted by: {sortBy}</div>
            <select onChange={(e) => changeSortBy(e)}>
                <option value='default'>Default</option>
                <option value='updated_at'>Recently Added</option>
                <option value='pinyin'>Pinyin</option>
                <option value='freq'>Frequency</option>
                <option value='stroke_count'>Stroke Count</option>
                <option value='heisig_number'>Heisig Number</option>

            </select>

            <div className="characters_container">
                {
                    results.map((result, i) => {

                        return (<CharacterLink 
                                        key={result.id}
                                        hanzi={result} 
                                        currentSearchHanzi={currentSearchHanzi}
                                        lastCharacterRef={lastCharacterRef}
                                        currentCharIndex={i} 
                                        resultsLength={results.length}/>
                                );
                    })
                }
            </div>
            
            {displayLoading || loading ? <div className="spinner"><ClipLoader /></div> : null}
            
            {/* if the current page isn't 1, show last page button
            {currentPage !== 1 ?
                <button className="btn" onClick={() => changePage(currentPage - 1)}>Last Page</button> :
                null
            }

            // {/* if the current page isn't equal to the last page, show next page button */}
            {/* // {currentPage !== lastPage ?
            //     <button className="btn" onClick={() => changePage(currentPage + 1)}>Next Page</button> :
            //     null
            // } */}
          
        </div>
    )
}


if (document.getElementById('chars')) {
    const element = document.getElementById('chars');
    const props = Object.assign({}, element.dataset);
    ReactDOM.render(<Chars {...props}/>, element);
}