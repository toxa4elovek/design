class ClientLogo extends React.Component{
    constructor() {
        super();
        this.animationSpeed = 300;
        this.mouseEnter = this.mouseEnter.bind(this);
        this.mouseLeave = this.mouseLeave.bind(this);
    }
    mouseEnter(event) {
        this.targetToggle(event, 'enter');
    }
    mouseLeave(event) {
        this.targetToggle(event, 'leave');
    }
    targetToggle(event, type) {
        const target = $(event.target);
        let onImage = target;
        let image = target.prev();
        let imageOpacity = 0;
        let onImageOpacity = 1;
        if('on' != target.data('image-status')) {
            image = target;
            onImage = target.prev();
        }
        if('leave' === type) {
            imageOpacity = 1;
            onImageOpacity = 0;
        }
        image.animate({opacity: imageOpacity}, this.animationSpeed);
        onImage.animate({opacity: onImageOpacity}, this.animationSpeed);
    }
    render() {
        const link = '/pitches/view/' + this.props.id;
        return (
            <li className="partner-logo-item" style={{width: this.props.width, marginRight: '25px'}}>
                <a onMouseOver={this.mouseEnter} onMouseOut={this.mouseLeave} target="_blank" className="hoverlogo" href={link}>
                    <img style={{paddingTop: this.props.paddingTop + 'px'}} className="image-on" data-image-status="off" src={this.props.imageOff} alt={this.props.title} />
                    <img style={{paddingTop: this.props.paddingTop + 'px'}} className="image-off" data-image-status="on" src={this.props.imageOn} alt={this.props.title} />
                </a>
            </li>
        );
    }
}