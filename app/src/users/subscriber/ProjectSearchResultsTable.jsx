class ProjectSearchResultsTable extends React.Component{
    render() {
        if(this.props.payload.length == 0) {
            return (<div></div>);
        }
        let rows = [];
        this.props.payload.forEach(function (row) {
            if((row.type === 'company_project') && (row.status == 2) && (row.awarded == 0)) {
                const momentDate = moment(row.finishDate, 'YYYY-MM-DD HH:mm:ss');
                function formatMoney(value) {
                    value = value.toString().replace(/(.*)\.00/g, "$1");
                    let counter = 1;
                    while(value.match(/\w\w\w\w/)) {
                        value = value.replace(/^(\w*)(\w\w\w)(\W.*)?$/, "$1 $2$3");
                        counter ++;
                        if(counter > 6) break;
                    }
                    return value;
                }
                const formattedRefund = formatMoney(row.price - parseInt(row.extraFunds));
                const refundObject = {
                    "type": "refund",
                    "total": row.price - parseInt(row.extraFunds),
                    "formattedMoney": `+ ${formattedRefund}`,
                    "formattedDate": momentDate.format('DD.MM.YYYY')
                };
                rows.push(refundObject);
            }
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
                console.log(row.id);
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