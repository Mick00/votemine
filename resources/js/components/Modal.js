import React, {useState} from 'react';
import {Modal as BootstrapModal, Button} from "react-bootstrap";
import ReactDOM from "react-dom";
import CopyField from "./CopyField";

function Modal(props) {
    const [show, setShow] = useState(true);
    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);
    console.log(props);
    return (
        <BootstrapModal show={show} onHide={handleClose}>
            <BootstrapModal.Header closeButton>
                <BootstrapModal.Title>{props.title}</BootstrapModal.Title>
            </BootstrapModal.Header>
            <BootstrapModal.Body>
                <p>{props.body}</p>
                {props.type === "copy" &&
                    <div className={"mx-3"}><CopyField value={props.value}/></div>
                }
            </BootstrapModal.Body>
            <BootstrapModal.Footer>
                <Button variant="primary" onClick={handleClose}>Fermer</Button>
            </BootstrapModal.Footer>
        </BootstrapModal>
    );
}

export default Modal;
let modalElement = document.getElementById('modal');
if (modalElement) {
    let modalData = JSON.parse(modalElement.getAttribute('data-modal'));
    ReactDOM.render(<Modal {...modalData}/>, document.getElementById('modal'));
}
