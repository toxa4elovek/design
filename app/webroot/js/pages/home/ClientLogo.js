var ClientLogo = React.createClass({displayName: "ClientLogo",
    animationSpeed: 300,
    mouseEnter: function(event) {
        var target = $(event.target);
        var onImage = target;
        var image = target.prev();
        if('on' != target.data('image-status')) {
            image = target;
            onImage = target.prev()
        }
        image.animate({opacity: 0}, this.animationSpeed);
        onImage.animate({opacity: 1}, this.animationSpeed);
    },
    mouseLeave: function() {
        var target = $(event.target);
        var onImage = target;
        var image = target.prev();
        if('on' != target.data('image-status')) {
            image = target;
            onImage = target.prev()
        }
        image.animate({opacity: 1}, this.animationSpeed);
        onImage.animate({opacity: 0}, this.animationSpeed);
    },
    render: function () {
        var link = '/pitches/view/' + this.props.id;
        return (
            React.createElement("li", {className: "partner-logo-item", style: {width: this.props.width, marginRight: '25px'}}, 
                React.createElement("a", {onMouseOver: this.mouseEnter, onMouseOut: this.mouseLeave, target: "_blank", className: "hoverlogo", href: link}, 
                    React.createElement("img", {style: {paddingTop: this.props.paddingTop + 'px'}, className: "image-on", "data-image-status": "off", src: this.props.imageOff, alt: this.props.title}), 
                    React.createElement("img", {style: {paddingTop: this.props.paddingTop + 'px'}, className: "image-off", "data-image-status": "on", src: this.props.imageOn, alt: this.props.title})
                )
            )
        );
    }
});