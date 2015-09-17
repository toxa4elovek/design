class ProjectSearchBar extends React.Component{
    placeholder = "найдите свой  проект по ключевому слову или типу";
    constructor() {
        super();
        this.arrowLinkClick = this.arrowLinkClick.bind(this);
        this.searchButtonClick = this.searchButtonClick.bind(this);
        this.handleFocus = this.handleFocus.bind(this);
        this.handleBlur = this.handleBlur.bind(this);
    }
    arrowLinkClick(event) {
        const arrow = $(this.refs.arrowIndicator);
        const dir = arrow.data('dir');
        let imageUrl = '/img/filter-arrow-up.png';
        if ('up' == dir) {
            arrow.data('dir', 'down');
        } else {
            imageUrl = '/img/filter-arrow-down.png';
            arrow.data('dir', 'up');
        }
        arrow.attr('src', imageUrl);
        event.preventDefault();
    }
    searchButtonClick(event) {
        const data = {
            "query": this.refs.searchInput.value
        };
        event.preventDefault();
        $.get('/users/subscriber.json', data, function(response) {
            console.log(response.payload);
            ReactDOM.render(
                <ProjectSearchResultsTable payload={response.payload}/>,
                document.getElementById('subscribe-project-search-results')
            );
        });
    }
    handleFocus() {
        const container = $(this.refs.filterContainer);
        const textField = $(this.refs.searchInput);
        container.css({"border": '4px solid #E7E7E7', "box-shadow": 'none'});
        textField.css('box-shadow', 'none');
        //textField.removeAttr('placeholder')
    }
    handleBlur() {
        const container = $(this.refs.filterContainer);
        const textField = $(this.refs.searchInput);
        container.css({"border": '4px solid #F3F3F3', "box-shadow": '0 1px 2px rgba(0, 0, 0, 0.2) inset'});
        textField.css('box-shadow', '1px 1px 2px rgba(0, 0, 0, 0.2) inset');
        /*if('' == textField.val()) {
            textField.attr('placeholder', this.placeholder);
        }*/
    }
    /*componentDidMount: function() {
     React.findDOMNode(this.refs.searchInput).placeholder = "Enter a Date";
     console.log(React.findDOMNode(this.refs.searchInput))
     },*/
    render() {
        return (
            <table>
                <tbody>
                    <tr>
                        <td>
                            <div ref="filterContainer" className="search-box-container">
                                <ul className="tags" id="filterbox"></ul>
                                <input ref="searchInput" type="text" placeholder={this.placeholder} onFocus={this.handleFocus} onBlur={this.handleBlur} onChange={this.handleChange} className="placeholder" style={{"color": "#666666"}}/>
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
        )
    }
}