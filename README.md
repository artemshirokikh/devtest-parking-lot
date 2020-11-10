# PARKING LOT (Developer Test)

To know about the task you can in **PHP Backend Developer Test.pdf** file in this repository

## LAUNCH

You can run application with simple internal PHP webserver with command:

    php -S localhost:8000

launched from project root directory (directory of index.php).

## STATE

Initially application has one parking lot with *id* = **F1**. Which has

* 2 places for motorbikes
* 2 places for cars
* 2 places for buses

*(These settings could be changed in the configuration file **config.php**)*

All these parking places are occupied with vehicles. See table below:

    [
        {"id":"M01","registrationNumber":"M01","vehicleType":"car","parkingPlaceType":"car","parkedAt":"2020-11-10 11:47:13"},
        {"id":"M04","registrationNumber":"M04","vehicleType":"bus","parkingPlaceType":"bus","parkedAt":"2020-11-10 11:50:37"},
        {"id":"M05","registrationNumber":"M05","vehicleType":"motorbike","parkingPlaceType":"motorbike","parkedAt":"2020-11-10 11:51:04"},
        {"id":"M06","registrationNumber":"M06","vehicleType":"motorbike","parkingPlaceType":"motorbike","parkedAt":"2020-11-10 12:02:35"},
        {"id":"M07","registrationNumber":"M07","vehicleType":"motorbike","parkingPlaceType":"car","parkedAt":"2020-11-10 12:03:01"},
        {"id":"M09","registrationNumber":"M09","vehicleType":"bus","parkingPlaceType":"bus","parkedAt":"2020-11-10 12:03:57"}
    ]

## ARCHITECHTURE

Apart target task's classes application has also simple implementations of such layers as:

* Configuration
* Routing
* Controllers
* Collections
* Enumerations
* Persistence

## API

1. Get all parked vehicles at parking lot with ID = *{parking_lot_id}*

    ```
    GET /parking_lots/{parking_lot_id}/vehicles
    ```

    *Response:*

        [
            {
                "id": "M01",
                "registrationNumber": "M01",
                "vehicleType": "car",
                "parkingPlaceType": "car",
                "parkedAt": "2020-11-10 11:47:13"
            },{
                "id": "M04",
                "registrationNumber": "M04",
                "vehicleType": "bus",
                "parkingPlaceType": "bus",
                "parkedAt":"2020-11-10 11:50:37"
            },
            ...
        ]


2. Park vehicle at parking lot

    ```
    POST /parking_lots/{parking_lot_id}/vehicles
    ```

        {
            "vehicleType": "car",
            "registrationNumber": "M01"
        }

    *Response:*

        {
            "id": "M09",
            "registrationNumber": "M09",
            "vehicleType": "bus",
            "parkingPlaceType": "bus"
        }


3. Depart vehicle from parking lot

    ```
    DELETE /parking_lots/{parking_lot_id}/vehicles/{vehicle_parking_id}
    ```

## CURL EXAMPLES

1) SHOW PARKED VEHICLES

```
curl --request GET \
http://localhost:8000/parking_lots/F1/vehicles
```

2) PARK VEHICLE

```
curl --header "Content-Type: application/json" \
--request POST \
--data '{"vehicleType":"car","registrationNumber":"M13"}' \
http://localhost:8000/parking_lots/F1/vehicles
```

3) DEPART VEHICLE

```
curl --request DELETE \
http://localhost:8000/parking_lots/F1/vehicles/M03
```

# Thank you a lot! ^_^
