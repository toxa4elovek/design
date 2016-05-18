class ProjectSearchResultsTableRow extends React.Component{
    render () {
        const row = this.props.row;
        const viewLink = '/pitches/view/' + row.id;
        let status = '';
        let balanceBefore = {
            "fontSize": "15px",
            "fontWeight": "normal",
            "color": "#999"
        };
        let statusStyle = {
            "fontWeight": "bold",
            "color": "#fff"
        };
        if(row.status == 0) {
            status = row.startedHuman;
            statusStyle = {
                "fontSize": "15px",
                "textTransform": "none",
                "fontWeight": "bold",
                "color": "#fff"
            };
        }
        if((row.type !== 'plan-payment') && (row.status == 2) && (row.awarded == 0) && (row.type !== 'fund-balance')) {
            statusStyle = {};
            status = 'оформлен возврат';
        }
        if(((row.status == 2) && (row.awarded != 0)) || ((row.status == 2) && (row.type == 'plan-payment'))) {
            let downloadDocs = '';
            let downloadAct = '';

            if ((row.hasBill == 'fiz') || (row.hasBill == 'yur')) {
                const docsUrl = '/pitches/getpdfreport/' + row.id;
                downloadDocs = <a class="pitches-finish" href={docsUrl} target="_blank">СКАЧАТЬ ОТЧЁТ</a>;
            }
            if (row.hasBill == 'yur') {
                const actUrl = '/pitches/getpdfact/' + row.id;
                downloadAct = <a class="pitches-finish" href={actUrl} target="_blank">СКАЧАТЬ акт</a>;
            }
            const closingUrl = '/users/step3/' + row.awarded;
            let linkToClosing = '';
            if(row.type != 'plan-payment') {
                linkToClosing = <a href={closingUrl} target="_blank">Перейти на заверш. этап</a>;
            }
            status = <div className="table-link-container">{linkToClosing}<br/>{downloadDocs}<br/>{downloadAct}</div>;
        }
        if((row.status == 1) && (row.awarded == 0)) {
            status = 'выбор победителя';
        }
        let title = row.title;
        let dateAfterTitle = '';
        if(row.type === 'company_project') {
            title = <a href={viewLink} target="_blank">{title}</a>;
        }else if(row.type === 'fund-balance'){
            balanceBefore = {
                "fontSize": "15px",
                "fontWeight": "normal",
                "color": "#cdcbcc"
            };
            statusStyle = {
                "fontSize": "15px",
                "textTransform": "none",
                "fontWeight": "bold",
                "color": "#fff"
            };
            row.tableClass = 'newpitch';
            status = <span style={statusStyle}>{row.formattedDate}</span>;
        }
        if(row.type === 'refund') {
            balanceBefore = {
                "fontSize": "15px",
                "fontWeight": "normal",
                "color": "#cdcbcc"
            };
            statusStyle = {
                "fontSize": "15px",
                "textTransform": "none",
                "fontWeight": "bold",
                "color": "#fff"
            };
            row.tableClass = 'newpitch';
            title = <a href={viewLink} target="_blank">«{row.projectTitle}» — возврат</a>;
            status = <span style={statusStyle}>{row.formattedDate}</span>;
        }
        if(row.type === 'addon') {
            balanceBefore = {
                "fontSize": "15px",
                "fontWeight": "normal",
                "color": "#cdcbcc"
            };
            statusStyle = {
                "fontSize": "15px",
                "textTransform": "none",
                "fontWeight": "bold",
                "color": "#fff"
            };
            row.tableClass = 'newpitch';
            status = <span style={statusStyle}>{row.formattedDate}</span>;
            title = <a href={viewLink} target="_blank">{row.title}</a>;
        }
        function formatMoney(value) {
            value = value.toString().replace(/(.*)\.00/g, "$1");
            let counter = 1;
            while(value.match(/\w\w\w\w/)) {
                value = value.replace(/(\w*)(\w\w\w)(\W.*)?$/, "$1 $2$3");
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
                <td className="pitches-time" style={statusStyle}>{status}</td>
                <td className="price" style={{"textAlign": "left", "paddingLeft": "48px"}}><span style={{"opacity": "0"}}>- </span><span style={balanceBefore}>{formattedMoney}</span><br/>{row.formattedMoney}</td>
            </tr>
        );
    }
}