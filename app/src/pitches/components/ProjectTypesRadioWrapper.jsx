class ProjectTypesRadioWrapper extends React.Component {
    render () {
        return (<div>
            {this.props.data.map(function(props) {
                return (
                    <ProjectTypesRadio data={props}
                        key={props.id}
                    />
                );
            })}
        </div>);
    }
}