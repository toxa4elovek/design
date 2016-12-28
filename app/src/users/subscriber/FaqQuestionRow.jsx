class FaqQuestionRow extends React.Component{
    render() {
        const link = 'https://godesigner.ru/answers/view/' + this.props.row.id;
        return (
            <li>
                <a href={link}>{this.props.row.title}</a>
            </li>
        );
    }
}