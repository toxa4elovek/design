class ProjectSearchResultsTableHeader extends React.Component{
    render() {
        return (
            <thead>
            <tr>
                <td>
                    <a href="#" id="sort-title" className="sort-link" data-dir="asc">название</a>
                </td>
                <td>
                    <a href="#" id="sort-ideas_count" className="sort-link" data-dir="asc">идей</a>
                </td>
                <td>
                    <a href="#" id="sort-finishDate" className="sort-link" data-dir="asc">срок/статус</a>
                </td>
                <td>
                    <a href="#" id="sort-price" className="sort-link" data-dir="asc">Цена</a>
                </td>
            </tr>
            </thead>
        )
    }
}