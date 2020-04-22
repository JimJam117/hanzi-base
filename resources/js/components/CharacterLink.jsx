import React from 'react'

export default function CharacterLink(props) {
    let result = props.hanzi;

    let translations = "";

    if(result.translations) {
        let translationsArr = result.translations.match(/[a-zA-Z0-9]+/gi);
        
        // remove any duplicates
        translationsArr = [...new Set(translationsArr)];

        let letterCount = 0;
        let i = 0;

        do {
            if(translationsArr[i]){
                let comma = (translations == "") ? "" : ", ";
                translations = translations.concat(comma + translationsArr[i]); 
                letterCount = letterCount + translationsArr[i].length;
            }
            i++;
        }
        while(letterCount < 10 && i < translationsArr.length);
    }


    const isRadical = result.radical == result.char;

    return (
        
        <a  ref={props.resultsLength - 1 == (props.currentCharIndex) ? props.lastCharacterRef : null} 
            href={`/character/${result.char}`} 
            className={props.currentSearchHanzi && props.currentSearchHanzi.indexOf(result.char) == -1 ? `character_link` : `character_link currentSearchHanzi`}>
        {/* {{-- Top details, radical and trad/simp --}} */}
        <div className={"details top-details " + (isRadical ? "active" : "")}> 
            {isRadical ? (props.hasSimplified ? <p>Traditional Radical</p> : props.hasTraditional ? <p>Simplified Radical</p> : <p>Radical</p>) :  <p>{result.radical}</p> }
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
