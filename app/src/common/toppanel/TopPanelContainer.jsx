class TopPanelContainer extends BaseComponent {
    render() {
        const data = this.props.data;
        return (
            <div id="pitch-panel">
                <div className="conteiner">
                    <div className="content">
                        <table className="all-pitches" id="header-table">
                            <tbody>
                            {data.map(function(row, index) {
                                if((index +1) % 2 ){
                                    row.tableClass = 'odd';
                                }else {
                                    row.tableClass = 'even';
                                }
                                return (<TopPanelRow key={row.id} data={row}/>);
                            })}
                            </tbody>
                        </table>
                        <p className="pitch-buttons-legend">
                            <a href="https://godesigner.ru/answers/view/6">
                                <i id="help"></i>
                                Какие способы оплаты вы принимаете?
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        )
    }
}