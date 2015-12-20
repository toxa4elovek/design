import React from 'react';
import ClientLogo from './ClientLogo.jsx';

export default class ClientsLogosShowcase extends React.Component{
    render() {
        return (<ul className="logos" >{this.props.data.map(function(object) {
            return (
                <ClientLogo
                    id={object.id}
                    imageOff={object.imageOff}
                    imageOn={object.imageOn}
                    key={object.id}
                    paddingTop={object.paddingTop}
                    title={object.title}
                    width={object.width}
                    />
            );
        })}</ul>);
    }
}