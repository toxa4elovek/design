class ReceiptAccessor {
    static add(array, value) {
        array = this.removeByName(array, value.name);
        array.push(value);
        return array;
    }
    add(array, value) {
        array = this.removeByName(array, value.name);
        array.push(value);
        return array;
    }
    static removeByName(array, name) {
        let indexToRemove = 0;
        array.forEach(function (row) {
            if (row.name == name) {
                indexToRemove = array.indexOf(row);
            }
        });
        if (indexToRemove > 0) {
            array.splice(indexToRemove, 1);
        }
        return array;
    }
    removeByName(array, name) {
        let indexToRemove = 0;
        array.forEach(function (row) {
            if (row.name == name) {
                indexToRemove = array.indexOf(row);
            }
        });
        if (indexToRemove > 0) {
            array.splice(indexToRemove, 1);
        }
        return array;
    }
    static get(array, name) {
        let value = null;
        array.forEach(function (row) {
            if (row.name == name) {
                value = row.value;
            }
        });
        return value;
    }
    get(array, name) {
        let value = null;
        array.forEach(function (row) {
            if (row.name == name) {
                value = row.value;
            }
        });
        return value;
    }
    static exists(array, name) {
        let result = false;
        array.forEach(function (row) {
            if (row.name == name) {
                result = true;
            }
        });
        return result;
    }
    exists(array, name) {
        let result = false;
        array.forEach(function (row) {
            if (row.name == name) {
                result = true;
            }
        });
        return result;
    }
}