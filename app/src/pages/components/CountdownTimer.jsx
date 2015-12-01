class CountdownTimer extends React.Component {
    constructor(props) {
        super();
        this.deadLine = props.data;
    }
    componentDidMount() {
        this.setInterval(this.tick, 1000);
    }
    setInterval() {
        setInterval(function() {
            this.setState({deadLine: moment().format('YYYY-MM-DD HH:mm:ss')});
        }.bind(this), 1000);
    }
    render () {
        const eventTime= moment(this.deadLine, 'YYYY-MM-DD HH:mm:ss').format('x');
        const currentTime = moment().format("x");
        const diffTime = eventTime - currentTime;
        const duration = moment.duration(diffTime, 'milliseconds');
        const days = duration.days();
        let hours = duration.hours();
        if(hours.toString().length < 2) {
            hours = `0${hours}`;
        }
        let minutes = duration.minutes();
        if(minutes.toString().length < 2) {
            minutes = `0${minutes}`;
        }
        let seconds = duration.seconds();
        if(seconds.toString().length < 2) {
            seconds = `0${seconds}`;
        }
        let timeString = `${days} дн. ${hours}:${minutes}:${seconds}`;
        if(days == 0) {
            timeString = `${hours}:${minutes}:${seconds}`;
        }
        return (
            <div>
                <p style={{"color": "#666"}}>Предложение действительно<br/>
                <span style={{"color": "#f14965"}}>{timeString}</span></p>
            </div>
        );
    }
}