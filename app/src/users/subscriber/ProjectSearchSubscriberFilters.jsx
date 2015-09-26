class ProjectSearchSubscriberFilters extends React.Component {
    render() {
        let style = {
            "borderRadius": "10px",
            "paddingTop": "14px",
            "marginLeft": "6px",
            "width": "600px",
            "height": "150px",
            "zIndex": "10",
            "position": "absolute",
            "backgroundColor": "white"
        };
        let merged = $.extend(style, this.props.styles);
        return (
            <div style={style}>
                <ul className="filterlist" style={{"float":"left", "width": "105px", "marginLeft": "25px", "textTransform": "none"}}>
                    <li className="first">проекты</li>
                    <li style={{"width": "85px"}}><a data-group="type" data-value="all" href="#">все</a></li>
                    <li style={{"width": "85px"}}><a data-group="type" data-value="current" href="#">текущие</a></li>
                    <li style={{"width": "85px"}}><a data-group="type" data-value="finished" href="#">завершенные</a></li>
                </ul>
                <ul className="filterlist"
                    style={{"float":"left", "width": "105px", "marginLeft": "25px", "textTransform": "none"}}
                >
                    <li className="first" style={{"width": "90px"}}>сроки</li>
                    <li style={{"width": "90px"}}><a data-group="timeframe" data-value="1" href="#">до 3 дней</a></li>
                    <li style={{"width": "90px"}}><a data-group="timeframe" data-value="2" href="#">до 7 дней</a></li>
                    <li style={{"width": "90px"}}><a data-group="timeframe" data-value="3" href="#">до 10 дней</a></li>
                    <li style={{"width": "90px"}}><a data-group="timeframe" data-value="4" href="#">более 14 дней</a></li>
                </ul>
                <ul className="filterlist" style={{"float":"left", "width": "160px", "marginLeft": "25px", "textTransform": "none"}}>
                    <li className="first">гонорар</li>
                    <li style={{"width": "130px"}}><a data-group="priceFilter" data-value="3" href="#">от 20 000 Р.-</a></li>
                    <li style={{"width": "130px"}}><a data-group="priceFilter" data-value="2" href="#">от 10 000 - 20 000 Р.-</a></li>
                    <li style={{"width": "130px"}}><a data-group="priceFilter" data-value="1" href="#">от 5 000 - 10 000 Р.-</a></li>
                </ul>
                <div style={{"clear": "both"}}></div>
            </div>
        );
    }
}