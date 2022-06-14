import React, { Fragment, useState, useEffect } from 'react'
import { SelectInput, withDataProvider } from 'react-admin'
import _ from 'lodash'
import { useField } from 'react-final-form'
import { withStyles } from '@material-ui/core/styles'

const styles = theme => ({
    categorySelectInput: {
        width: '240px',
    }
})

const CategoryInputComponent = ({ dataProvider, classes, record }) => {

    const [categories, setCategories] = useState([])
    const [depths, setDepths] = useState([])

    const { input, meta } = useField('selectedCategories')
    const categoryIdInput = useField('category_id')

    const selectedCategories = JSON.stringify(input.value)
    const ancestors = record && record.category && record.category.ancestors
    
    const getCategories = () => {
        dataProvider.getMany('taxonomies', {
            // Fake ids for react-admin to send the request
            ids: [0],
            filter: {
                // Instruct rest client to not apply ids filter
                all: true,
                type: 'category',
                tree: '1',
            }
        }).then(res => {
            setCategories(res.data)
            // Get unique depths sorted asc
            setDepths(_.uniq(_.map(res.data, 'depth')).sort((a, b) => a - b))
        }).catch(err => {
            alert('Failed to fetch categories')
        })
    }

    // Set the default selectedCategories for edit
    useEffect(() => {
        if ( ! ancestors || categories.length === 0 || ! record.category) {
            return
        }
        // Get the selected categories sorted hierarchically
        const selectedCats = _.map(record.category.ancestors.sort((a, b) => a.depth - b.depth), 'id')
        selectedCats.push(record.category.id)
        input.onChange(selectedCats)
    }, [ancestors, categories.length])

    useEffect(() => {
        getCategories()
    }, [])

    useEffect(() => {
        categoryIdInput.input.onChange(getLastValue(input.value))
    }, [selectedCategories])

    const getLastValue = (arr) => arr[arr.length - 1]

    const hasChildren = catId => {
        return !!categories.find(c => c.parent_id == catId)
    }

    const getCurrentDepth = cats => {
        if ( ! cats) return 0
        // Check consistency of categories
        // i.e, Check that each selected category
        // is a child of previous one and truncate
        // the depth at inconsistency if exists
        for (var i = 0; i < cats.length; i++) {
            const catId = cats[i]
            const parentId = cats[i - 1]
            if (i === 0) continue
            const category = categories.find(c => c.id == catId && c.parent_id == parentId)
            if ( ! category) {
                // Inconsistent categories selected reset selection
                // At the first consistent choice
                // Change the input value to only contain consistent categories
                input.onChange(cats.slice(0, i))
                return hasChildren(parentId) ? i : i - 1
            }
        }

        // If the last selected category doesn't have more children
        // don't display the next select input
        if ( ! hasChildren(cats[cats.length - 1])) {
            return cats.length - 1
        }
        
        return cats.length
    }

    const currentDepth = getCurrentDepth(input.value)

    return (
        <Fragment>
            {depths.map(depth => depth <= currentDepth && (
                <div key={`category-${depth}`}>
                    <SelectInput
                        className={classes.categorySelectInput}
                        label={depth == 0 ? 'Select root category' : 'Select sub category'}
                        source={'selectedCategories.'+depth}
                        optionValue="id"
                        optionText="title.en"
                        choices={categories.filter(cat => {
                            const sameDepth = cat.depth === depth
                            const matchParent = depth == 0 ||
                                cat.parent_id == input.value[depth - 1]
                            return sameDepth && matchParent
                        })}
                    />
                </div>
            ))}
        </Fragment>
    )
}

export default withStyles(styles)(withDataProvider(CategoryInputComponent))
