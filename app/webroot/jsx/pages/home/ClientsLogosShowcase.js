var ClientsLogosShowCase = React.createClass({
    render: function() {
        return (<ul className="logos" >{this.props.data.map(function(object) {
            return (
                <ClientLogo
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
});