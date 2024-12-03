import Tesseract from "tesseract.js";

const members = ['藥吹', '藥炎', '藥頭', 'Rushh', '藥夯', '藥嗨', '藥奶', '藥去了', '藥涼', '森上', 'Bear', 'Machillz', '藥精', '烏拉妮雅', '膏肓痛痛丸', '紅黑單雙', '很秀阿a', 'KOKE', 'YHao', '武翠紅'];

document.addEventListener('alpine:init', () => {
    Alpine.bind('NamePicInput', () => ({
        'data-result': null, worker: null, async 'x-init'() {
            this.worker = await Tesseract.createWorker('chi_tra');
            console.log('tesseract init');
        }, async '@paste'(e) {
            this.$el.disabled = true;
            try {
                for (const item of e.clipboardData.files) {
                    console.log(item);
                    if (item.type.startsWith('image/')) {
                        const pasted = URL.createObjectURL(item);
                        const result = await scanName(this.worker, pasted);

                        document.querySelectorAll('[data-result="name"]')
                            .forEach(el => {
                                el.value = [el.value, ...result].join('\n').trim();
                                el.scrollTop = el.scrollHeight;
                                el.style.height = 'auto';
                                el.style.height = el.scrollHeight + 'px';
                            });
                    }
                }
            } finally {
                this.$el.disabled = false;
            }
        },
    }));
});

const isNumber = (value) => typeof value === 'number' && isFinite(value);

const scanName = async function (worker, file) {
    const res = await worker.recognize(file);
    let result = Array();

    let possibleLv = Array(), bbLvStart = null, bbLvEnd = null, name = '', skip = false;
    // Draw bounding boxes around recognized words
    res.data.words.forEach((word) => {
        // console.log(word.text)

        let n = Number(word.text);
        if (isNumber(n) && n > 10) {
            // console.log(word);
            // possible lv number
            possibleLv.push(word);
            return;
        }

        if (word.text.includes('等')) {
            // drawBB(word);
        }


        if (word.text.includes('級')) {
            name = '';
            // found end of level line, stop skipping
            skip = false;
            // drawBB(word);
            bbLvEnd = word;
            possibleLv.some((i) => {
                if (isSameLine(bbLvEnd, i)) {
                    // it's the lv number, so it's start of level line.
                    bbLvStart = i;
                    // drawBB(i);
                    return true;
                }

                return false;
            });

            return;
        }

        if (bbLvStart !== null && bbLvEnd !== null && !skip) {
            // bounding box of level line
            let x0 = bbLvStart.bbox.x0;
            let y0 = Math.min(bbLvStart.bbox.y0, bbLvEnd.bbox.y0);
            let y1 = Math.max(bbLvStart.bbox.y1, bbLvEnd.bbox.y1);
            let h = y1 - y0;
            let xTolerance = (bbLvEnd.bbox.x1 - bbLvEnd.bbox.x0) / 2;
            let yTolerance = (bbLvEnd.bbox.y1 - bbLvEnd.bbox.y0) / 2;

            // name line in following range:
            //   1. top lower than level line (y0)
            //   2. bottom higher than 2x height of level line (y1, with tolerance)
            //   3. left aligned (x0, with tolerance)
            let isName = word.bbox.y0 > y0 && word.bbox.y1 < (y1 + 2 * h + yTolerance) && word.bbox.x0 > x0 - xTolerance;
            if (isName) {
                name = name + word.text.trim();
                console.log(name);
                if (members.includes(name)) {
                    result.push(name);
                    // const newParagraph = document.createElement('p')
                    // newParagraph.textContent = name
                    // memberFound.appendChild(newParagraph)

                    // found member, reset name and skip to until next bounding
                    name = '';
                    skip = true;
                }
            }
        }
    });

    return result;
};

/**
 * @param {Word} sub
 * @param {Word} chk
 *
 * check if chk is same line as subject(sub)
 */
const isSameLine = (sub, chk) => {
    let tolerance = (sub.bbox.y1 - sub.bbox.y0) / 2;

    return chk.bbox.y1 < (sub.bbox.y1 + tolerance) && chk.bbox.y0 > (sub.bbox.y0 - tolerance);
};
