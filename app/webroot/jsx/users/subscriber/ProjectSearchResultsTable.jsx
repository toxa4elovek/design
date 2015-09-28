class ProjectSearchResultsTable extends React.Component{
    render() {
        return (
            <table id="myprojects" className="project-search-table">
                <ProjectSearchResultsTableHeader/>
                <tbody>
                    {this.props.payload.map(function(row, index) {
                        if((index +1) % 2 ){
                            row.tableClass = 'odd';
                        }else {
                            row.tableClass = 'even';
                        }
                        return (
                            <ProjectSearchResultsTableRow key={row.id} row={row} />
                        )
                    })}
                </tbody>
            </table>
        )
    }
}