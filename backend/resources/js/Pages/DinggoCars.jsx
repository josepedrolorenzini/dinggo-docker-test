import { Head } from '@inertiajs/react'
import React from 'react'

function DinggoCars({ cars }) {
    return (
        <>
            <Head title="Dinggo Cars" />
            <div className="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
                <h1>Dinggo Cars</h1>
                <ul>
                    {cars.map(car => (
                        <li key={car.vin}>{car.make} {car.model}</li>
                    ))}
                </ul>
            </div>
        </>
    )
}

export default DinggoCars
