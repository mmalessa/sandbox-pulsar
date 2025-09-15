import http from 'k6/http';
import { sleep } from 'k6';
import { Rate } from 'k6/metrics';

export let errorRate = new Rate('errors');

export let options = {
    vus: 1,        // liczba wirtualnych użytkowników
    duration: '5s', // czas trwania testu
    // rps: 50         // requestów na sekundę (opcjonalnie)
};

let globalCounter = 0;

export default function () {

    const timestamp = Date.now();
    globalCounter++;

    const url = 'http://php:8080/api-test';
    const payload = JSON.stringify({
        vu: __VU,
        iter: __ITER,
        counter: globalCounter,
        timestamp: timestamp
    });

    const params = {
        headers: {
            'Content-Type': 'application/json'
        },
    };

    let res = http.post(url, payload, params);
    if (res.status !== 200) {
        errorRate.add(1);
    }

    // sleep(1); // regulacja interwału pomiędzy requestami, opcjonalnie
}
