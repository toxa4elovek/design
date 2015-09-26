class ProjectSearchResultsTable extends React.Component{
    render() {
        if(this.props.payload.length == 0) {
            return (<div></div>);
        }
        return (
            <table
                className="project-search-table"
                id="myprojects"
            >
                <ProjectSearchResultsTableHeader/>
                <tbody>
                    {this.props.payload.map(function(row, index) {
                        if((index +1) % 2 ){
                            row.tableClass = 'odd';
                        }else {
                            row.tableClass = 'even';
                        }
                        return (
                            <ProjectSearchResultsTableRow
                                key={row.id}
                                row={row}
                            />
                        );
                    })}
                </tbody>
            </table>
        );
    }
}