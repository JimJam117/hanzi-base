import React from 'react'

export default function CharacterLink(props) {
    let result = props.hanzi;

    let translations = result.translations ? result.translations : null;
    if(translations) {
        translations = translations.substr(0, 20);

        let lastLetter = translations[translations.length - 1];
        if (lastLetter == ";" || lastLetter == "." || lastLetter == ",") {
            translations = translations.substr(0, translations.length -1);
        }
    }

    
    

    return (
        
        <a  ref={props.resultsLength - 1 == (props.currentCharIndex) ? props.lastCharacterRef : null} 
            href={`/character/${result.char}`} 
            className={props.currentSearchHanzi && props.currentSearchHanzi.indexOf(result.char) == -1 ? `character_link` : `character_link currentSearchHanzi`}>
        {/* {{-- Top details, radical and trad/simp --}} */}
        <div className="top-details"> 
            <p>{ result.radical }</p>
            <p>
                {props.hasSimplified ? "Trad" : props.hasTraditional ? "Simp" : null}
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

}
