import { Component, OnInit } from '@angular/core';
import { EditorService } from "../../services/editor.service";
import { LanguageService } from "../../services/language.service";
import { NotificationService } from "../../services/notification.service";
import { ProgressService } from "../../services/progress.service";
import { TDKService } from "../../services/tdk.service";
import { Dialect } from "../../models/dialect";
import { Lexeme } from "../../models/lexeme";
import { Semantics } from "../../models/semantics";
import { WordType, WordTypes } from "../../models/word-type";

@Component({
  selector: 'app-editor',
  templateUrl: './editor.component.html',
  styleUrls: ['./editor.component.css']
})
export class EditorComponent implements OnInit {
    lexeme = <Lexeme>{};
    languages: Object;
    tdkWord: string;
    dialects: Dialect[];
    wordTypes: WordType[] = WordTypes;
    editable: Boolean = false;

    constructor(private editorService: EditorService, private languageService: LanguageService,
                public progress: ProgressService, private tdkService: TDKService,
                private notificationService: NotificationService) {}

    addConnections(semanticId, languageId) {
        let newConnect = <Lexeme>{};
        let newSemantics = <Semantics>{};
        newConnect.language_id = languageId;
        newConnect.semantics_list = [newSemantics];
        this.lexeme.semantics_list[semanticId].connects.push(newConnect);
    }

    addMoreSource() {
        if (this.lexeme.etymon.sources.length < 3) {
            this.lexeme.etymon.sources.push({sample: '', reference: ''});
        }
    }

    clearConnect(connect) {
        if (connect.fetched) {
            connect.lexeme_id = null;
            connect.pronunciation = '';
            connect.fetched = false;
            connect.semantics_list[0] = <Semantics>{};
        }
    }

    getConnectionsByLanguageId(connects, languageId) {
        if (connects.length) {
            return connects.filter(c => Number(c.language_id) === Number(languageId));
        }
    }

    searchedLexeme(data) {
        let connects = this.lexeme.semantics_list[data.params.semanticsId].connects;
        let groupedConnects = this.getConnectionsByLanguageId(connects, data.params.languageId);
        let filtered = connects.filter(c => Number(c.language_id) !== Number(data.params.languageId));
        groupedConnects[data.params.connectId] = data.data;
        this.lexeme.semantics_list[data.params.semanticsId].connects = filtered.concat(groupedConnects);
    }

    getLanguages() {
        this.progress.circular = true;
        this.languageService.getLanguages().subscribe((res : any) => {
            this.progress.circular = false;
            if (res.status === 'success') {
                this.languages = res.data;
                this.dialects = [];
                res.data.forEach((lang) => {lang.status && lang.flag && Number(lang.language_id) !== 21 && this.dialects.push(lang); });
            } else {
                this.notificationService.show(res.message);
            }
        }, () => {
            this.progress.circular = false;
            this.notificationService.show('Bir şeyler ters gitti.');
        });
    }

    fetchFromTDK(word) {
        this.progress.circular = true;
        this.tdkService.getMeans(word).subscribe((res : any) => {
            this.progress.circular = false;
            if (res.status === 'success') {
                this.lexeme = res.data;
                this.editable = !!Number(this.lexeme.lexeme_id);
                if (!this.lexeme.etymon.sources) {
                    this.lexeme.etymon.sources = [{sample: '', reference: ''}];
                }
                this.notificationService.show(res.message);
            } else {
                this.notificationService.show(res.message);
            }
        }, () => {
            this.progress.circular = false;
            this.notificationService.show('Bir şeyler ters gitti.');
        });
    }

    removeConnect(semantics, languageId, connectId) {
        let filtered = semantics.connects.filter(c => Number(c.language_id) === Number(languageId));
        semantics.connects = semantics.connects.filter(c => Number(c.language_id) !== Number(languageId));
        filtered.splice(connectId, 1);
        semantics.connects = semantics.connects.concat(filtered);
    }

    removeSource(id) {
        this.lexeme.etymon.sources.splice(id, 1);
    }

    saveChanges() {
        this.progress.circular = true;
        if (this.editable) {
            this.update();
        } else {
            this.save();
        }
    }

    private save() {
        this.editorService.post(this.lexeme).subscribe((res: any) => {
            this.progress.circular = false;
            this.notificationService.show(res.message);
        }, () => {
            this.progress.circular = false;
            this.notificationService.show('Kaydetme sırasında bir şeyler ters gitti.');
        });
    }

    private update() {
        this.editorService.put(this.lexeme).subscribe((res: any) => {
            this.progress.circular = false;
            this.notificationService.show(res.message);
        }, () => {
            this.progress.circular = false;
            this.notificationService.show('Güncelleme sırasında bir şeyler ters gitti.');
        });
    }

    ngOnInit() {
        this.getLanguages();
    }

}
