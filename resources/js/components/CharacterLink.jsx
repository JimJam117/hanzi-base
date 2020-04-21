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

    const isRadical = result.radical == result.char;

    return (
        
        <a  ref={props.resultsLength - 1 == (props.currentCharIndex) ? props.lastCharacterRef : null} 
            href={`/character/${result.char}`} 
            className={props.currentSearchHanzi && props.currentSearchHanzi.indexOf(result.char) == -1 ? `character_link` : `character_link currentSearchHanzi`}>
        {/* {{-- Top details, radical and trad/simp --}} */}
        <div className={"details top-details " + (isRadical ? "active" : "")}> 
            {isRadical ? <p>Radical</p> :  <p>{result.radical}</p> }
            {!isRadical && (props.hasSimplified ? "Traditional" : props.hasTraditional ? "Simplified" : null)}
        </div>

        <h2 className="character">{result.char}</h2>

        {/* Pinyin */}
        <p className="pinyin">
            { result.pinyin }
        </p>
        
        {/* Translations */}
        <h3>
            {translations}
        </h3>
        


        <div className="details bottom-details">
            {result.heisig_keyword ? <>
                
                <div>{`${result.heisig_keyword} (${result.heisig_number})`}</div>
                </> : null}
        </div>
        
        </a> 
    )

}
