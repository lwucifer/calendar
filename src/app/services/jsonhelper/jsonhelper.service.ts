import {Injectable} from '@angular/core';

@Injectable()
export class JsonhelperService {

    constructor() {
    }

    private makeRandom(lengthOfCode: number, possible: string) {
        let text = '';
        for (let i = 0; i < lengthOfCode; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }
        return text;
    }

    public create_id(lengthOfCode = 20, possible = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890') {
        const timenow = new Date().getTime() / 1000;
        const string = this.makeRandom(lengthOfCode, possible);

        return timenow + string;
    }

    private isJson(str): boolean {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }

    public encode(json): string {
        return JSON.stringify(json);
    }

    public decode(json_string): JSON {
        if (this.isJson(json_string)) {
            const json = JSON.parse(json_string);
            return json;
        } else {
            return null;
        }
    }

    public json2array(json): any {
        return Object.keys(json).map(k => json[k]);
    }
}
