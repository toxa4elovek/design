class ProjectSearchResultsTableHeader extends React.Component{
    onClickHandler(e) {
        const currentDir = $(e.target).data('dir');
        e.preventDefault();
        if(currentDir == 'asc') {
            $(e.target).attr('data-dir', 'desc');
        }else {
            $(e.target).attr('data-dir', 'asc');
        }
    }
    render() {
        return (
            <thead>
            <tr>
                <td style={{"textAlign": "left", "paddingLeft": "45px"}}>
                    <a href="#" onClick={this.onClickHandler.bind(this)} id="sort-title" className="sort-link" data-dir="asc">название</a>
                </td>
                <td>
                    <a href="#" onClick={this.onClickHandler.bind(this)} id="sort-ideas_count" className="sort-link" data-dir="asc">идей</a>
                </td>
                <td>
                    <a href="#" onClick={this.onClickHandler.bind(this)} id="sort-finishDate" className="sort-link" data-dir="asc">срок/статус</a>
                </td>
                <td>
                    <a href="#" onClick={this.onClickHandler.bind(this)} id="sort-price" className="sort-link" data-dir="asc">Цена</a>
                </td>
            </tr>
            </thead>
        )
    }
}