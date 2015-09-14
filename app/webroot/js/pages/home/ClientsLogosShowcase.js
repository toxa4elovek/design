"use strict";

var ClientsLogosShowCase = React.createClass({
    displayName: "ClientsLogosShowCase",

    render: function render() {
        return React.createElement(
            "ul",
            { className: "logos" },
            this.props.data.map(function (object) {
                return React.createElement(ClientLogo, {
                    title: object.title,
                    id: object.id,
                    imageOn: object.imageOn,
                    imageOff: object.imageOff,
                    width: object.width,
                    paddingTop: object.paddingTop
                });
            })
        );
    }
});