import {Component} from '@angular/core';
import {HttpHeaders} from '@angular/common/http';
import {PhotoBaseComponent} from '../photo-base.component';

const ParseHeaders = {
    headers: new HttpHeaders({
        'Content-Type': 'application/js; charset=UTF-8'
    })
};

@Component({
    selector: 'app-photo-add',
    templateUrl: './../photo-base.component.html',
    styleUrls: ['./photo-add.component.css']
})

export class PhotoAddComponent extends PhotoBaseComponent {
    init() {
        this.getCash();
        this.setFormDataValue(this.initOption());
        this.loaded = true;
    }

    onSubmit(data) {
        this.post('./api/photo', JSON.stringify(data), ParseHeaders).subscribe(res => {
            this.handleSubmit(res);
        });
    }
}

