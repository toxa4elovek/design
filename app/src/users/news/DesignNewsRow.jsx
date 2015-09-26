class DesignNewsRow extends React.Component {
    render() {
        const newsInfo = this.props.newsInfoForRow;
        return (
            <div className="design-news">
                <a target="_blank" href={newsInfo.trackLink}>{newsInfo.title}</a> <br/>
                <a className="clicks" href={newsInfo.trackLink}>{newsInfo.host.host}</a>
            </div>
        );
    }
}