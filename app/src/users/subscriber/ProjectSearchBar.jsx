class ProjectSearchBar extends React.Component{
    constructor() {
        super();
        this.arrowLinkClick = this.arrowLinkClick.bind(this);
        this.searchButtonClick = this.searchButtonClick.bind(this);
        this.handleFocus = this.handleFocus.bind(this);
        this.handleBlur = this.handleBlur.bind(this);
        this.placeholder = "найдите свой  проект по ключевому слову или типу";
    }
    arrowLinkClick(event) {
        const arrow = this.refs.arrowIndicator;
        const dir = arrow.getAttribute('data-dir');
        let imageUrl = '/img/filter-arrow-up.png';
        if ('up' == dir) {
            arrow.setAttribute('data-dir', 'down');
        } else {
            imageUrl = '/img/filter-arrow-down.png';
            arrow.setAttribute('data-dir', 'up');
        }
        arrow.setAttribute('src', imageUrl);
        event.preventDefault();
        if(this.props.settings.isFilterListActive) {
            data.settings.isFilterListActive = false;
        }else {
            data.settings.isFilterListActive = true;
        }
        ReactDOM.render(
            <SubscriberPage data={data} />,
            document.getElementById('pageMount')
        );
    }
    searchButtonClick(event) {
        const data = {
            "query": this.refs.searchInput.value
        };
        event.preventDefault();
        fetch('/users/subscriber.json', {
            credentials: 'same-origin'
        }).then(function(response){
            response.json().then(function(json) {
                ReactDOM.render(
                    <ProjectSearchResultsTable payload={json.payload}/>,
                    document.getElementById('subscribe-project-search-results')
                );
            });
        });
    }
    handleFocus() {
        const container = $(this.refs.filterContainer);
        const textField = $(this.refs.searchInput);
        container.css({"border": '4px solid #E7E7E7', "box-shadow": 'none'});
        textField.css('box-shadow', 'none');
    }
    handleBlur() {
        const container = $(this.refs.filterContainer);
        const textField = $(this.refs.searchInput);
        container.css({"border": '4px solid #F3F3F3', "box-shadow": '0 1px 2px rgba(0, 0, 0, 0.2) inset'});
        textField.css('box-shadow', '1px 1px 2px rgba(0, 0, 0, 0.2) inset');
    }
    render() {
        if(this.props.payload.length == 0) {
            return (<div></div>);
        }
        let filterListStyle = {"display": "none"};
        if(this.props.settings.isFilterListActive == true) {
            filterListStyle = {"display": "block"};
        }
        return (
            <div>
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <div ref="filterContainer" className="search-box-container">
                                    <ul className="tags" id="filterbox">

                                    </ul>
                                    <input ref="searchInput" type="text" placeholder={this.placeholder} onFocus={this.handleFocus} onBlur={this.handleBlur} className="placeholder" style={{"color": "#666666"}}/>
                                    <a href="#" onClick={this.arrowLinkClick} className="arrow-container">
                                        <img ref="arrowIndicator" className="arrow-down" src="/img/filter-arrow-down.png" data-dir="up" alt="Раскрыть меню" />
                                    </a>
                                    <a href="#" id="filterClear"></a>
                                </div>
                            </td>
                            <td>
                                <a href="#" onClick={this.searchButtonClick} className="button clean-style-button start-search">Поиск</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <ProjectSearchSubscriberFilters styles={filterListStyle}/>
            </div>
        )
    }
}