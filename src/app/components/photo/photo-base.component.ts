import {FormArray, Validators} from '@angular/forms';
import {BaseComponent} from '../../app/base.component';

export class PhotoBaseComponent extends BaseComponent {
    id;
    cash;
    options: FormArray;
    sameInputSelectOption = [];
    arraySelectOptions = [];
    inputChoiceOptions = [];
    editMode = [];
    index;
    numberAddOption = 1;

    getCash() {
        this.get('./api/cash').subscribe(res => {
            this.cash = res;
            this.cash = this.cash.response.data;
        });
    }


    setFormDataValue(option = null) {
        if (option !== null) {
            this.arraySelectOptions = [];
            this.inputChoiceOptions = [];
            this.editMode = [];
        }
        return this.formdata = this.fb.group({
            name: ['', Validators.required],
            comment: [''],
            cash_id: ['', Validators.required],
            option: this.fb.array(
                option === null ? [option] : []
            )
        });
    }

    setOptions() {
        let formOption;
        let arraySelect;
        this.options = this.formdata.get('option') as FormArray;

        const content = JSON.parse(this.results.option[0].content);
        let i = 0;
        for (const option of content) {
            arraySelect = [];
            this.editMode.push([]);
            for (const select of option.select) {
                arraySelect.push(select);
                this.editMode[i].push(false);
            }
            i++;
            this.arraySelectOptions.push(arraySelect);
            const index = this.arraySelectOptions.length - 1;
            formOption = this.fb.group({
                id: [option.id],
                name: [option.name],
                select: [this.arraySelectOptions[index]],
                type: [option.type],
                require: [option.require === true],
                weekday_benefits: [''],
                holiday_benefits: ['']
            });
            this.options.push(formOption);
        }
    }

    clearEditMode() {
        for (let i = 0; i < this.editMode.length; i++) {
            for (let j = 0; j < this.editMode[i].length; j++) {
                this.editMode[i][j] = false;
            }
        }
    }

    initEditMode(index, select) {
        for (let i = 0; i < select.length; i++) {
            this.editMode[index][i] = false;
        }
    }

    initDrag() {
        this.drag.drag('CLICKS').subscribe(drag => {
            this.clearEditMode();
        });

        this.drag.dropModel('CLICKS').subscribe(drag => {
            const idx = drag.el['dataset'].id;
            this.arraySelectOptions[idx] = drag.sourceModel;
            this.formdata.controls['option']['controls'][idx].value.select = drag.sourceModel;
        });
    }

    initOption() {
        this.arraySelectOptions.push([]);
        this.inputChoiceOptions.push('');
        this.editMode.push([]);
        const lengthDefaultOptions = this.arraySelectOptions.length - 1;

        return this.fb.group({
            id: [this.createOptionId()],
            name: [''],
            select: [this.arraySelectOptions[lengthDefaultOptions]],
            type: ['1'],
            require: [true],
            weekday_benefits: [''],
            holiday_benefits: ['']
        });
    }

    setFormDefault(option = {}) {
        return this.fb.group({
            id: [option['id']],
            name: [option['name'] || ''],
            select: [option['select'] || ''],
            type: [option['type'] || ''],
            require: [option['require'] || ''],
            weekday_benefits: [option['weekday_benefits'] || ''],
            holiday_benefits: [option['holiday_benefits'] || '']
        });
    }

    createOptionId() {
        const stringDate = new Date().toISOString().slice(0, 10).replace(/-/g, '');
        const randomString = Math.random().toString(36).substring(7);
        return stringDate + randomString;
    }

    addOption() {
        this.options = this.formdata.get('option') as FormArray;
        for (let i = 0; i < this.numberAddOption; i++) {
            this.options.push(this.initOption());
        }
    }

    removeOption(id) {
        this.options.removeAt(id);
        this.arraySelectOptions.splice(id, 1);
    }

    addSelectOption(index) {
        if (this.inputChoiceOptions[index] === '') {
            return;
        }

        for (let i = 0; i < this.arraySelectOptions[index].length; i++) {
            if (this.arraySelectOptions[index][i].name === this.inputChoiceOptions[index]) {
                this.sameInputSelectOption[index] = true;
                return;
            } else {
                this.sameInputSelectOption[index] = false;
            }
        }

        const json = {
            name: this.inputChoiceOptions[index],
            'holiday_price': '',
            'weekday_price': '',
        };

        this.arraySelectOptions[index].unshift(json);
        this.editMode[index].push(false);
        this.inputChoiceOptions[index] = '';
    }

    deleteSelectOption(indexOption, indexSelectOption) {
        this.arraySelectOptions[indexOption].splice(indexSelectOption, 1);
        this.editMode[indexOption].splice(indexSelectOption, 1);
    }

    handleSubmit(res) {
        super.getResponseMessageSubmit(res);
        if (this.success) {
            super.checkSubmit('photo');
        }
    }
}
