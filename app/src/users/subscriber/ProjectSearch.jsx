class ProjectSearch extends React.Component {
    render() {
        const payload = this.props.payload;
        const tags = this.props.tags;
        if(payload.length == 0) {
            return (<div></div>);
        }
        return (
            <div>
                <section className="project-search-widget" >
                    <ProjectSearchBar payload={payload} settings={settings}/>
                </section>
                <section className="project-search-results" >
                    <ProjectSearchResultsTable payload={payload}/>
                </section>
            </div>
        );
    }
}