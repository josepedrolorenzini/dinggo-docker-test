import { Head } from '@inertiajs/react';
import { useEffect } from 'react';

export default function Welcome({ cars }) {

    useEffect(() => {
        console.log("Cars data:", cars);
    }, []);

    return (
        <>
            <Head title="Cars" />
            <div className="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
                <div>
                    Check console

                    <pre>{JSON.stringify(cars, null, 2)}</pre>
                </div>
            </div>
        </>)
}
