class ProjectSearch extends React.Component {
    render() {
        const payload = this.props.payload;
        const tags = this.props.tags;
        if(payload.length == 0) {
            return (<div></div>);
        }
        const bottomShadow = {"boxShadow": "0px 10px 15px -15px rgba(0,0,0,0.45)"};
        return (
            <div>
                <section className="project-search-widget" style={bottomShadow}>
                    <ProjectSearchBar payload={payload} settings={settings}/>
                </section>
                <section className="project-search-results" >
                    <ProjectSearchResultsTable payload={payload}/>
                </section>
            </div>
        );
    }
}