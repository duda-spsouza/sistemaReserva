SELECT	
     CONCAT(CONCAT(emp.first_name,' '),emp.last_name) employee_name
    ,dep.dept_name
    ,DATEDIFF(to_date,from_date) days_in_dept
FROM 
	employees emp
INNER JOIN dept_emp dep_emp ON
	emp.emp_no = dep_emp.emp_no
INNER JOIN departments dep ON
	dep_emp.dept_no = dep.dept_no
ORDER BY
	days_in_dept DESC
LIMIT 10