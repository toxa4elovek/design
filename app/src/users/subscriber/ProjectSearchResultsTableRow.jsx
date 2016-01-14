class ProjectSearchResultsTableRow extends React.Component{
    render () {
        const row = this.props.row;
        const viewLink = '/pitches/view/' + row.id;
        let ideas = '';
        let status = '';
        if(row.status == 0) {
            status = row.startedHuman;
        }
        if((row.type !== 'plan-payment') && (row.status == 2) && (row.awarded == 0) && (row.type !== 'fund-balance')) {
            status = 'оформлен возврат';
        }
        if(((row.status == 2) && (row.awarded != 0)) || ((row.status == 2) && (row.type == 'plan-payment'))) {
            let downloadDocs = '';
            let downloadAct = '';

            if ((row.hasBill == 'fiz') || (row.hasBill == 'yur')) {
                const docsUrl = '/pitches/getpdfreport/' + row.id;
                downloadDocs = <a style={{'color': '#4f8b5c'}} class="pitches-finish" href={docsUrl} target="_blank">СКАЧАТЬ ОТЧЁТ</a>;
            }
            if (row.hasBill == 'yur') {
                const actUrl = '/pitches/getpdfact/' + row.id;
                downloadAct = <a style={{'color': '#4f8b5c'}} class="pitches-finish" href={actUrl} target="_blank">СКАЧАТЬ акт</a>;
            }
            const closingUrl = '/users/step3/' + row.awarded;
            let linkToClosing = '';
            if(row.type != 'plan-payment') {
                linkToClosing = <a style={{'color': '#4f8b5c'}} href={closingUrl} target="_blank">Скачать исходники</a>;
            }
            status = <div className="table-link-container">{linkToClosing}<br/>{downloadDocs}<br/>{downloadAct}</div>;
        }
        if((row.status == 1) && (row.awarded == 0)) {
            status = 'выбор победителя';
        }
        let title = row.title;
        let dateAfterTitle = '';
        const dateAfterTitleStyle = {
            "fontSize": "12px",
            "fontWeight": "bold"
        };
        if(row.type == 'company_project') {
            ideas = row.ideas_count;
            title = <a href={viewLink} target="_blank">{title}</a>;
        }else if(row.type == 'fund-balance'){
            row.tableClass = 'light';
            dateAfterTitle = <span style={dateAfterTitleStyle}>({row.formattedDate})</span>;
        }
        if(row.type === 'refund') {
            row.tableClass = 'light';
            title = 'Возврат';
            dateAfterTitle = <span style={dateAfterTitleStyle}>({row.formattedDate})</span>;
        }
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
        const formattedMoney = formatMoney(this.props.subtotal);
        return (
            <tr className={row.tableClass}
                data-id={row.id}
            >
                <td className="td-title">
                    <span className="newpitchfont">{title}</span> {dateAfterTitle}
                </td>
                <td className="idea">{ideas}</td>
                <td className="pitches-time">{status}</td>
                <td className="price">{row.formattedMoney}<br/><span style={dateAfterTitleStyle}>({formattedMoney} р.-)</span></td>
            </tr>
        );
    }
}