class ProjectSearchResultsTable extends React.Component{
    render() {
        if(this.props.payload.length == 0) {
            return (<div></div>);
        }
        let rows = [];
        this.props.payload.forEach(function (row) {
            if((row.type === 'company_project') && (row.status == 2) && (row.awarded == 0)) {
                const formattedMoney = row.formattedMoney.replace(/^-/, '+');
                const momentDate = moment(row.finishDate, 'YYYY-MM-DD HH:mm:ss');
                const refundObject = {
                    "type": "refund",
                    "formattedMoney": formattedMoney,
                    "formattedDate": momentDate.format('DD.MM.YYYY')
                };
                rows.push(refundObject);
            }
            rows.push(row);
        });
        return (
            <table
                className="project-search-table"
                id="myprojects"
            >
                <ProjectSearchResultsTableHeader/>
                <tbody>
                    {rows.map(function(row, index) {
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