class ProjectSearchResultsTable extends React.Component{
    render() {
        if(this.props.payload.length == 0) {
            return (<div></div>);
        }
        let rows = [];
        this.props.payload.forEach(function (row) {
            rows.push(row);
        });
        const copyForCalculations = rows.slice().reverse();
        let operations = [0];
        copyForCalculations.forEach(function (row) {
            let result = 0;
            if((row.type === 'company_project') || (row.type === 'multiwinner')) {
                result -= parseInt(row.price);
            }else if((row.type === 'fund-balance') || (row.type === 'refund')) {
                result += parseInt(row.total);
            }else if(row.type === 'addon') {
                result -= parseInt(row.total);
            }
            const lastResult = operations[operations.length - 1];
            operations.push(lastResult + result);
        });
        operations = operations.reverse().slice(1);
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
                                subtotal={operations[index]}
                            />
                        );
                    })}
                </tbody>
            </table>
        );
    }
}