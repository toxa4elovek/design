class ProjectSearchResultsTableRow extends React.Component{
    render () {
        const row = this.props.row;
        const link = '/pitches/view/' + row.id;
        let ideas = '';
        let status = '';
        let title = row.title;
        let dateAfterTitle = '';
        if(row.type == 'company-project') {
            ideas = row.ideas_count;
        }else if(row.type == 'fund-balance'){
            row.tableClass = 'light';
            let dateAfterTitleStyle = {
                "fontSize": "12px",
                "fontWeight": "bold"
            };
            dateAfterTitle = <span style={dateAfterTitleStyle}>({row.formattedDate})</span>;
        }
        return (
            <tr className={row.tableClass}
                data-id={row.id}
            >
                <td className="td-title">
                    <span className="newpitchfont">{title}</span> {dateAfterTitle}
                </td>
                <td className="idea">{ideas}</td>
                <td className="pitches-time">{status}</td>
                <td className="price">{row.formattedMoney}</td>
            </tr>
        );
    }
}