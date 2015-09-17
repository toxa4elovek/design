class ProjectSearchResultsTableRow extends React.Component{
    render () {
        const row = this.props.row;
        const link = '/pitches/view/' + row.id;
        let ideas = '';
        let status = '';
        if(row.type == 'company-project') {
            ideas = row.ideas_count;
        }else if(row.type == 'fund-balance'){
            row.tableClass = 'light';
        }
        return (
            <tr data-id={row.id} className={row.tableClass}>
                <td className="td-title">
                    <span className="newpitchfont">{row.title}</span>
                </td>
                <td className="idea">{ideas}</td>
                <td className="pitches-time">{status}</td>
                <td className="price">{row.formattedMoney}</td>
            </tr>
        )
    }
}