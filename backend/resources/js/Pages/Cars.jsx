import { Head } from '@inertiajs/react';
import { useEffect } from 'react';

export default function Welcome({ cars }) {

    useEffect(() => {
        console.log("Cars data:", cars);
    }, []);

    return (
        <>
            <Head title="Cars" />
            <div>
                Check console

                <pre>{JSON.stringify(cars, null, 2)}</pre>
            </div>
        </>)
}
