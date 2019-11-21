import {HttpClient, HttpHeaders} from '@angular/common/http';

const ParseHeaders = {
    headers: new HttpHeaders({
        'Content-Type': 'application/x-www-form-urlencoded'
    })
};

export class ApiRequest {
    protected http: HttpClient;

    get(path) {
        return this.http.get(path);
    }

    post(url, data, parseHeaders = ParseHeaders) {
        return this.http.post(url, data, parseHeaders);
    }

    del(path) {
        return this.http.delete(path);
    }

}
