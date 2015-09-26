class ProjectSearchResultsTableHeader extends React.Component{
    onClickHandler(e) {
        const attributeName = 'data-dir';
        if(e.target.hasAttribute(attributeName)) {
            const currentDir = e.target.getAttribute(attributeName);
            e.preventDefault();
            if('asc' === currentDir) {
                e.target.setAttribute(attributeName, 'desc');
            }else {
                e.target.setAttribute(attributeName, 'asc');
            }
        }
    }
    render() {
        return (
            <thead>
            <tr>
                <td style={{"textAlign": "left", "paddingLeft": "45px"}}>
                    <a href="#" onClick={this.onClickHandler.bind(this)} className="sort-link" data-dir="asc">название</a>
                </td>
                <td>
                    <a href="#" onClick={this.onClickHandler.bind(this)} className="sort-link" data-dir="asc">идей</a>
                </td>
                <td>
                    <a href="#" onClick={this.onClickHandler.bind(this)} className="sort-link" data-dir="asc">срок/статус</a>
                </td>
                <td>
                    <a href="#" onClick={this.onClickHandler.bind(this)} className="sort-link" data-dir="asc">Цена</a>
                </td>
            </tr>
            </thead>
        )
    }
}