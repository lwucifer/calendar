import {Component, Input} from '@angular/core';
import {BaseComponent} from '../../app/base.component';
import {HttpHeaders} from '../../../../node_modules/@angular/common/http';

declare var jquery: any;
declare var $: any;

const ParseHeaders = {
    headers: new HttpHeaders({
        'Content-Type': 'application/x-www-form-urlencoded'
    })
};

@Component({
    selector: 'app-import',
    templateUrl: './import.component.html',
    styleUrls: ['./import.component.css']
})

export class ImportComponent extends BaseComponent {
    @Input() attrImport: string;
    @Input() object;

    results;
    public error = [];
    success;
    total = 0;
    files;
    file;
    fileName;
    fileSize;
    fileType;
    data;
    index;
    sv_file = true;
    message;

    handleFileSelect(evt) {
        this.files = evt.target.files;
        this.file = this.files[0];
        this.fileName = this.file.name;
        this.fileSize = this.file.size;
        this.fileType = this.file.type;
        this.sv_file = false;
    }

    extractData(csv) {
        const url = './api/' + this.attrImport + '/import';
        const allTextLines = csv.split(/\r\n|\n/);
        const headers = allTextLines[0].split('","');
        const lines = [];
        for (let i = 0; i < allTextLines.length; i++) {
            const data = allTextLines[i].split('","');
            if (data.length === headers.length) {
                const tarr = [];
                for (let j = 0; j < headers.length; j++) {
                    tarr.push(data[j].replace('"', '').replace('"', ''));
                }
                lines.push(tarr);
            }
        }
        this.data = 'csv=' + JSON.stringify(lines)
            + '&fileType=' + this.fileType + '&fileSize=' + this.fileSize + '&count=' + headers.length;
        this.post(url, this.data, ParseHeaders).subscribe((res) => {
            this.results = res;
            this.message = this.results.response.status.message;
            if ( this.results.response.data ) {
                this.total = 0;
                this.total = this.results.response.data.row;
                this.error = this.results.response.data.rowError;
                this.success = this.results.response.data.rowSuccess.length;
                this.object.reload();
            }
        });
    }

    import() {
        this.total = 0;
        const reader = new FileReader();
        reader.readAsText(this.file);
        reader.onload = function () {
            this.extractData(reader.result);
        }.bind(this);

        $('#importModal').modal('hide');
        $('#importModal input').val('');
        this.fileName = '';
        this.sv_file = true;
    }

    showMessage(id) {
        return Object.values(this.error[id].message);
    }
}
