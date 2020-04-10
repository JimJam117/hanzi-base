import React, {useEffect, useState, useRef, useCallback} from 'react'
import ReactDOM from 'react-dom';

import { css } from "@emotion/core";
import ClipLoader from "react-spinners/ClipLoader";


export default function Chars(props) {

    // abort controller
    var controller = new AbortController();
    var signal = controller.signal;



    const [loading, setLoading] = useState(true);
    const [displayLoading, setDisplayLoading] = useState(false);
    const [results, setResults] = useState([]);

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

    let query = "/api/chars/index";
    if (props.radical) {
        query = `/api/radical/search/${props.radical}`;
    } 

    const fetchItems = async (apiUrl = `${query}?page=${currentPage}`) =>  {
        console.log("load");
                await fetch(apiUrl, {signal})
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

                        console.log(currentPage, data.chars);
                        if(results.length > 0){
                            console.log("heres", [...results, ...data.chars.data]);
                            setResults([...results, ...data.chars.data]);
                        }
                        else{
                            setResults(data.chars.data);
                        }
                        

                        setCurrentPage(data.chars.current_page);
                        setLastPage(data.chars.last_page);
                        setLoading(false);
                })
            }

    useEffect(() => {
        if (loading) {fetchItems()}
        return () => {
            controller.abort();
        };
    }, [loading])




    return (
    
        <div>
            <div className="characters_container">
                {
                    results.map((result, i) => {
                        let hasSimplified = result.simp_char ? true : false;
                        let hasTraditional = result.trad_char ? true : false;

                        if (hasTraditional) {
                            let trads = result.trad_char;
                            trads = trads.split(",");

                            // if has same trad as char, then does not have a traditional version
                            trads.forEach((trad) => {
                                if (trad == result.char){
                                    hasTraditional = false;
                                }
                            }) 
                        }

                        // if the char is the same as the simp_char, then does not have simplified version
                        if(result.char == result.simp_char) {
                            hasSimplified = false;
                        }

                        let translations = result.translations.substr(0, 20);
                        //let translations = s.substr(0, strrpos($s, ' '));

                        let lastLetter = translations[translations.length - 1];
                        if (lastLetter == ";" || lastLetter == "." || lastLetter == ",") {
                            translations = translations.substr(0, -1);
                        }
                        translations.concat("..");
          
                        

                        return (
                            results.length - 1 == (i) ? 
                            <a ref={lastCharacterRef} key={`character${result.id}`} href={`/character/${result.char}`} className="character_link">
                            {/* {{-- Top details, radical and trad/simp --}} */}
                            <div className="top-details"> 
                                <p>{ result.radical }</p>
                                <p>
                                    {hasSimplified ? "Trad" : hasTraditional ? "Simp" : null}
                                </p>
                            </div>

                            <h2 className="character">{result.char}</h2>

                            {/* {{-- Translations or heisig --}} */}
                            <h3>
                                {result.heisig_keyword ? `H ${result.heisig_keyword} (${result.heisig_number})` : translations}
                            </h3>
                            
                            {/* {{-- Pinyin --}} */}
                            <p className="pinyin">{ result.pinyin }</p>
                            </a> : 
                            <a key={`character${result.id}`} href={`/character/${result.char}`} className="character_link">
                                {/* {{-- Top details, radical and trad/simp --}} */}
                                <div className="top-details"> 
                                    
                                    <p>{ result.radical }</p>
                                    <p>
                                        {hasSimplified ? "Trad" : hasTraditional ? "Simp" : null}
                                    </p>
                                </div>

                                <h2 className="character">{result.char}</h2>

                                {/* {{-- Translations or heisig --}} */}
                                <h3>
                                    {result.heisig_keyword ? `H ${result.heisig_keyword} (${result.heisig_number})` : translations}
                                </h3>

                                {/* {{-- Pinyin --}} */}
                                <p className="pinyin">{ result.pinyin }</p>
                            </a>
                        )

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