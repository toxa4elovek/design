var ClientLogo = React.createClass({
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
            <li className="partner-logo-item" style={{width: this.props.width, marginRight: '25px'}}>
                <a onMouseOver={this.mouseEnter} onMouseOut={this.mouseLeave} target="_blank" className="hoverlogo" href={link}>
                    <img style={{paddingTop: this.props.paddingTop + 'px'}} className="image-on" data-image-status="off" src={this.props.imageOff} alt={this.props.title} />
                    <img style={{paddingTop: this.props.paddingTop + 'px'}} className="image-off" data-image-status="on" src={this.props.imageOn} alt={this.props.title} />
                </a>
            </li>
        );
    }
});