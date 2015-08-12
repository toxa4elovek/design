var NewProjectBox = React.createClass({
    render: function() {
        return (
            <div>
                <label>Сумма вознаграждения победителю (от 500р)<span>*</span></label>

                <input className="price-input" type="text" name="price" placeholder="500" />
                <label>название проекта<span>*</span></label>

                <input type="text" name="title" className="title-input" placeholder="Бланк для письма" />
            </div>
        )
    }
});