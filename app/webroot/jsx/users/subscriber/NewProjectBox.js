var NewProjectBox = React.createClass({
    render: function() {
        return (
            <div>
                <label>Сумма вознаграждения победителю (от 500р)<span>*</span></label>

                <input className="price-input" type="text" name="price" placeholder="500" />
                <label>название проекта<span>*</span></label>

                <input type="text" name="title" className="title-input" placeholder="Бланк для письма" />

                <div className="clear"></div>
                <label className="date-label">дата окончания приёма работ</label>
                <label className="time-label">время</label>
                <div className="clear"></div>

                <input type="text" className="date-input" />
                <input type="text" className="date-input" />
                <input type="text" className="date-input year" />

                <input type="text" className="date-input" />
                <input type="text" className="date-input last-block" />
                <div className="clear"></div>

                <a href="#" className="button silver-button clean-style-button create-project-button">создать проект</a>

            </div>
        )
    }
});