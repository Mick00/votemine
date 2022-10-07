import React, {useRef, useState, useEffect} from "react";
import { Form, Row, InputGroup, Button, Overlay, Tooltip} from 'react-bootstrap';
import ReactDOM from "react-dom";
import copy from 'copy-to-clipboard';

function Copiable(props) {
    const [show, setShow] = useState(false);
    const clickable = useRef(null);

    function copyToClipboard(e) {
        copy(props.text);
        setShow(true);
    }
    useEffect(()=>{
        if (show){
            setTimeout(()=>setShow(false), 1500);
        }
    },[show]);
    return (
        <>
            <Overlay target={clickable.current} show={show} placement="bottom">
                {(props) => (
                    <Tooltip {...props}>
                        Copié!
                    </Tooltip>
                )}
            </Overlay>
            <span onClick={copyToClipboard} ref={clickable}>
                {props.text}
                    {document.queryCommandSupported('copy') && <i className="ml-2 far fa-copy"></i>}
            </span>
        </>
    );
}

export default Copiable;
let copiableFields = document.getElementsByClassName('copiable');
for(let i = 0; i< copiableFields.length; i++){
    let element = copiableFields.item(i);
    let props = element.getAttribute('data-props');
    ReactDOM.render(<Copiable {...props} text={element.innerHTML}/>, element);
}
