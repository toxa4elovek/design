class ProjectSearchResultsTable extends React.Component{
    render() {
        return (
            <table id="myprojects" className="project-search-table">
                <ProjectSearchResultsTableHeader/>
                <tbody>
                    <ProjectSearchResultsTableRow/>
                </tbody>
            </table>
        )
    }
}