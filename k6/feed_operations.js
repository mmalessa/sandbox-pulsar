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
let operations = [
    { walletId: 101, change: 100, success: true },
    { walletId: 101, change: -10, success: false },
    { walletId: 102, change: 200, success: true },
    { walletId: 103, change: 50, success: true },
    { walletId: 101, change: 60, success: true },
    { walletId: 101, change: -100, success: true },
    { walletId: 103, change: 51, success: true },
    { walletId: 103, change: 52, success: true },
    { walletId: 101, change: 160, success: true },
    { walletId: 102, change: -5, success: true },
    { walletId: 101, change: 11, success: true },
    { walletId: 101, change: 12, success: true },
    { walletId: 101, change: 13, success: true },
    { walletId: 101, change: 14, success: true },
];

export default function () {
    if (globalCounter >= operations.length) {
        return;
    }

    const timestamp = Date.now();
    const operation = operations[globalCounter];
    
    const url = 'http://php:8080/api-test';
    const payload = JSON.stringify({
        vu: __VU,
        iter: __ITER,
        counter: globalCounter,
        timestamp: timestamp,
        walletId: operation.walletId,
        change: operation.change,
        success: operation.success
    });

    globalCounter++;

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
