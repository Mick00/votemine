import React, {useRef, useState} from "react";
import { Form, Row, InputGroup, Button, Overlay, Tooltip} from 'react-bootstrap';
import ReactDOM from "react-dom";
import Modal from "./Modal";


function CopyField(props) {
    const [copySuccess, setCopySuccess] = useState(false);
    const [show, setShow] = useState(false);
    const input = useRef(null);
    function copyToClipboard(e) {
        input.current.select();
        setCopySuccess(document.execCommand('copy'));
        e.target.focus();
        setShow(true);
    }
    return (
        <Form.Group as={Row}>
            <Overlay target={input.current} show={show} placement="top">
                {(props) => (
                    <Tooltip {...props}>
                        Copié!
                    </Tooltip>
                )}
            </Overlay>
            <Form.Label column sm="2">
                {props.label}
            </Form.Label>
            <InputGroup>
                {copySuccess}
                <Form.Control ref={input} type="text" readOnly defaultValue={props.value} />
                {document.queryCommandSupported('copy') &&
                <InputGroup.Append>
                    <Button variant="outline-primary" onClick={copyToClipboard}>Copier</Button>
                </InputGroup.Append>
                }
            </InputGroup>
        </Form.Group>
    );
}

export default CopyField;
let copiableFields = document.getElementsByClassName('copy-input');
for(let i = 0; i< copiableFields.length; i++){
    let element = copiableFields.item(i);
    let value = element.getAttribute('data-value');
    ReactDOM.render(<CopyField value={value}/>, element);
}

