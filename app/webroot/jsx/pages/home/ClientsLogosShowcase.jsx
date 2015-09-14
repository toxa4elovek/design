class ClientsLogosShowCase extends React.Component{
    render() {
        return (<ul className="logos" >{this.props.data.map(function(object) {
            return (
                <ClientLogo
                    key={object.id}
                    title={object.title}
                    id={object.id}
                    imageOn={object.imageOn}
                    imageOff={object.imageOff}
                    width={object.width}
                    paddingTop={object.paddingTop}
                    />
            )
        })}</ul>)
    }
}