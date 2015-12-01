class ProfRadioList extends React.Component{
    render() {
        return (
            <div className="profselectbox-container">
                {this.props.data.map(function(props) {
                    return (
                        <ProfSelectBox data={props}
                            key={props.id}
                        />
                    );
                })}
            </div>
        );
    }
}